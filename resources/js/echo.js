import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
});

const channel = `news.1`;

window.Echo.channel(channel)
    .subscribed(() => {
        console.log("Successfully subscribed to channel:", channel);
    })
    .listen("CommentCreated", (e) => {
        console.log("CommentCreated event received:", e);
        const commentsContainer = document.getElementById("comments");
        commentsContainer.innerHTML += `
            <div class="comment">
                <div class="comment-body">
                        ID: ${e.id} коментаря
                    </div>
                <div class="comment-body">
                        ID: ${e.news_id} новини
                    </div>
                <div class="comment-author">
                    Автор: ${e.user?.name || "Невідомий"}
                </div>
                <div class="comment-body">
                   Текст: ${e.body}
                </div>
                <div class="comment-date">
                    Дата: ${new Date(e.created_at).toLocaleString("uk-UA")}
                </div>
            </div>
        `;
    })
    .error((error) => {
        console.log("Error:", error);
    });
