import { createServer } from "http";
import { Server as SocketIOServer } from "socket.io";
import axios from "axios";
import { log } from "console";

const server = createServer();
const io = new SocketIOServer(server, {
    cors: {
        origin: "http://localhost:8000",
        // or with an array of origins
        // origin: ["https://my-frontend.com", "https://my-other-frontend.com", "http://localhost:3000"],
        credentials: true,
    },
});

// Enable CORS

// URL endpoint AdminController pada aplikasi Laravel
const laravelEndpoint = "http://localhost:8000/antrians/navigate";

// Map to store Socket.IO clients and their corresponding CSRF tokens
const clientCSRFMap = new Map();

io.on("connection", (socket) => {
    console.log("User connected");

    // Listen for 'setCSRFToken' event to set the CSRF token for the client
    socket.on("setCSRFToken", (csrfToken) => {
        console.log("CSRF Token received:", csrfToken);
        clientCSRFMap.set(socket.id, csrfToken);
    });

    // Listen for 'navigateAntrian' events
    socket.on("navigateAntrian", async (data) => {
        const { direction } = data;

        // try {
        // Get CSRF token for the client
        const csrfToken = socket.handshake.query.csrfToken;

        if (!csrfToken) {
            throw new Error("CSRF token not found");
        }

        // Send HTTP request to Laravel to handle queue navigation
        const response = await axios.post(
            laravelEndpoint,
            { direction },
            {
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            }
        );

        console.log("Response from Laravel:", response.data);

        // Emit an 'updateQueue' event to inform all clients about the updated queue state
        io.emit("updateQueue", response.data);
        // } catch (error) {
        //     console.error("Error navigating queue:", error.message);
        // }
    });

    // Handle disconnection
    socket.on("disconnect", () => {
        console.log("User disconnected");

        // Remove CSRF token mapping for the disconnected client
        clientCSRFMap.delete(socket.id);
    });

    // Inform the client about its socket ID
    socket.emit("socketID", socket.id);
});

// Start the server
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Socket.IO server is running on http://localhost:${PORT}`);
});
