import React, { useState, useEffect, useCallback } from 'react';
import { createRoot } from 'react-dom/client';
import { startLogin, refreshToken } from '../../public/js/auth.js';

function Comments({ postId, userName, currentUserId }) {
    const [token, setToken] = useState(localStorage.getItem('access_token'));
    const [comments, setComments] = useState([]);
    const [newBody, setNewBody] = useState('');

    const wsUrl =
        window.location.protocol === 'https:'
            ? `wss://${window.location.hostname}/ws`
            : `ws://localhost:8000/ws`;

    const authFetch = useCallback(async (url, options = {}) => {
        let accessToken = localStorage.getItem('access_token');

        const request = (t) =>
            fetch(url, {
                ...options,
                credentials: 'include',
                headers: {
                    ...(options.headers || {}),
                    Authorization: `Bearer ${t}`,
                    'Content-Type': 'application/json',
                },
            });

        let response = await request(accessToken);


        if (response.status === 401) {
            const newToken = await refreshToken();

            if (!newToken) {
                localStorage.removeItem('access_token');
                setToken(null);
                startLogin();
                throw new Error('Auth expired');
            }

            localStorage.setItem('access_token', newToken);
            setToken(newToken);

            response = await request(newToken);
        }

        return response;
    }, []);

    const loadComments = useCallback(async () => {
        if (!token) return;

        const res = await authFetch(
            `https://api.vyache.space/api/posts/${postId}/comments`
        );

        if (res.ok) {
            const data = await res.json();
            setComments(data);
        }
    }, [token, postId, authFetch]);

    useEffect(() => {
        loadComments();
    }, [loadComments]);

    useEffect(() => {
        if (!token) return;

        const ws = new WebSocket(wsUrl);

        ws.onmessage = (e) => {
            const msg = JSON.parse(e.data);

            if (msg.type === 'new_comment') {
                setComments((prev) => [msg.comment, ...prev]);
            }

            if (msg.type === 'update_comment') {
                setComments((prev) =>
                    prev.map((c) =>
                        c.id === msg.comment.id
                            ? { ...c, body: msg.comment.body }
                            : c
                    )
                );
            }

            if (msg.type === 'delete_comment') {
                setComments((prev) =>
                    prev.filter((c) => c.id !== msg.comment_id)
                );
            }
        };

        return () => ws.close();
    }, [token, wsUrl]);

    const addComment = async () => {
        if (!token) return startLogin();
        if (!newBody.trim()) return;

        const res = await authFetch(
            `https://api.vyache.space/api/posts/${postId}/comments`,
            {
                method: 'POST',
                body: JSON.stringify({
                    body: newBody,
                    author_name: userName,
                }),
            }
        );

        if (res.ok) {
            setNewBody('');
        }
    };

    const updateComment = async (id, oldBody) => {
        if (!token) return;

        const newText = prompt('Новый текст', oldBody);
        if (!newText) return;

        await authFetch(`https://api.vyache.space/api/comments/${id}`, {
            method: 'PUT',
            body: JSON.stringify({ body: newText }),
        });
    };

    const deleteComment = async (id) => {
        if (!token) return;

        if (!confirm('Удалить?')) return;

        await authFetch(`https://api.vyache.space/api/comments/${id}`, {
            method: 'DELETE',
        });
    };

    return (
        <div>
            <h3>Комментарии</h3>

            {!token && (
                <button onClick={startLogin}>
                    Войти для комментариев
                </button>
            )}

            {token && (
                <>
                    <textarea
                        value={newBody}
                        onChange={(e) => setNewBody(e.target.value)}
                        rows="3"
                        style={{ width: '100%' }}
                    />

                    <button onClick={addComment}>Отправить</button>

                    <hr />

                    {comments.map((c) => (
                        <div
                            key={c.id}
                            style={{
                                border: '1px solid #ccc',
                                margin: '8px 0',
                                padding: '8px',
                            }}
                        >
                            <p>{c.body}</p>

                            <small>
                                {c.author_name} ·{' '}
                                {new Date(c.created_at).toLocaleString()}
                            </small>

                            {Number(c.author_id) ===
                                Number(currentUserId) && (
                                <div>
                                    <button
                                        onClick={() =>
                                            updateComment(c.id, c.body)
                                        }
                                    >
                                        Изменить
                                    </button>
                                    <button
                                        onClick={() => deleteComment(c.id)}
                                    >
                                        Удалить
                                    </button>
                                </div>
                            )}
                        </div>
                    ))}
                </>
            )}
        </div>
    );
}

const container = document.getElementById('comments-root');

if (container) {
    const root = createRoot(container);

    root.render(
        <Comments
            postId={parseInt(container.dataset.postId)}
            userName={container.dataset.userName}
            currentUserId={parseInt(container.dataset.userId)}
        />
    );
}