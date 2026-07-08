const { useState, useEffect } = React;

const API = 'https://api.vyache.space';
const PARENT_ID = 1;

function ItemList() {

    // Загрузка списка
    const [items, setItems] = useState([]);

    // Форма добавления
    const [text, setText] = useState('');

    // Редактирование
    const [editId, setEditId] = useState(null);
    const [editText, setEditText] = useState('');

    // GET
    const load = async () => {

        const res = await fetch(
            `${API}/api/posts/${PARENT_ID}/comments`
        );

        const data = await res.json();

        setItems(data.items);
    };

    // POST
    const add = async () => {

        if (!text.trim()) return;

        await fetch(
            `${API}/api/posts/${PARENT_ID}/comments`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    body: text
                })
            }
        );

        setText('');

        load();
    };

    // PUT
    const save = async (id) => {

        await fetch(
            `${API}/comments/${id}`,
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    body: editText
                })
            }
        );

        setEditId(null);

        load();
    };

    // DELETE
    const del = async (id) => {

        if (!confirm('Удалить?')) return;

        await fetch(
            `${API}/comments/${id}`,
            {
                method: 'DELETE'
            }
        );

        load();
    };

    // Загрузка при старте
    useEffect(() => {
        load();
    }, []);

    return (

        <div>

            {items.map(item => (

                <div key={item.id} className="card mb-2">

                    <div className="card-body">

                        <strong>
                            {item.author_name}
                        </strong>

                        {editId === item.id ? (

                            <div className="input-group mt-2">

                                <input
                                    className="form-control"
                                    value={editText}
                                    onChange={e => setEditText(e.target.value)}
                                />

                                <button
                                    className="btn btn-success"
                                    onClick={() => save(item.id)}
                                >
                                    Сохранить
                                </button>

                                <button
                                    className="btn btn-secondary"
                                    onClick={() => setEditId(null)}
                                >
                                    Отмена
                                </button>

                            </div>

                        ) : (

                            <div>

                                <p>
                                    {item.body}
                                </p>

                                <button
                                    className="btn btn-sm btn-outline-secondary"
                                    onClick={() => {
                                        setEditId(item.id);
                                        setEditText(item.body);
                                    }}
                                >
                                    ✏️
                                </button>

                                <button
                                    className="btn btn-sm btn-outline-danger"
                                    onClick={() => del(item.id)}
                                >
                                    🗑️
                                </button>

                            </div>

                        )}

                    </div>

                </div>

            ))}

            <div className="input-group mt-3">

                <input
                    className="form-control"
                    placeholder="Комментарий"
                    value={text}
                    onChange={e => setText(e.target.value)}
                />

                <button
                    className="btn btn-primary"
                    onClick={add}
                >
                    Отправить
                </button>

            </div>

        </div>

    );
}

ReactDOM
    .createRoot(document.getElementById('app'))
    .render(<ItemList />);
