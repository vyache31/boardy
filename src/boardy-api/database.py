import aiomysql

DB_CONFIG = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'boardy',
    'password': 'semyonov123',
    'db': 'boardy_api',
    'charset': 'utf8mb4',
}

async def get_db():
    return await aiomysql.connect(**DB_CONFIG)

async def db_query(sql: str, *args):
    conn = await get_db()
    async with conn.cursor(aiomysql.DictCursor) as cur:
        await cur.execute(sql, args)
        result = await cur.fetchall()
    conn.close()
    return result

async def db_query_one(sql: str, *args):
    conn = await get_db()
    async with conn.cursor(aiomysql.DictCursor) as cur:
        await cur.execute(sql, args)
        result = await cur.fetchone()
    conn.close()
    return result

async def db_execute(sql: str, *args):
    conn = await get_db()
    async with conn.cursor() as cur:
        await cur.execute(sql, args)
        await conn.commit()
    conn.close()

async def db_insert(sql: str, *args):
    conn = await get_db()
    async with conn.cursor() as cur:
        await cur.execute(sql, args)
        await conn.commit()
        return cur.lastrowid
    conn.close()