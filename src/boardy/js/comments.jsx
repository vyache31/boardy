const { useState, useEffect } = React;

const API = 'https://api.vyache.space';
const PARENT_ID = 1;


function ItemList() {

    const [jwt, setJwt] = useState(null);

    useEffect(() => {
        fetch('/api/me.php', {
            credentials: 'include'
        })
        .then(res => {
            if (!res.ok) throw new Error('no auth');
            return res.json();
        })
        .then(data => {
            if (data.token) setJwt(data.token);
        })
        .catch(() => setJwt(null));
    }, []);


    // Загрузка списка
    const [items, setItems] = useState([]);

    // Форма добавления
    const [text, setText] = useState('');

    // Редактирование
    const [editId, setEditId] = useState(null);
    const [editText, setEditText] = useState('');

    const headers = {
	'Content-Type': 'application/json',
    };
    if (jwt) {
	headers['Authorization'] = 'Bearer ' + jwt;
    }


    // GET
    const load = async () => {

        const res = await fetch(
            `${API}/api/posts/${PARENT_ID}/comments`,
	    {
		method: "GET",
		headers
	    }
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
                headers,
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
                headers,
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
                method: 'DELETE',
		headers
            }
        );

        load();
    };

    // Загрузка при старте
    useEffect(() => {
        load();
    }, [jwt]);

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
	    {jwt && (
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
	      )}
        </div>

    );
}

ReactDOM
    .createRoot(document.getElementById('app'))
    .render(<ItemList />);
