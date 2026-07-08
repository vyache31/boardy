import jwt
from fastapi import Header, HTTPException

PUBLIC_KEY = open('oauth-public.key').read()

async def get_current_user(authorization: str = Header(None)):
    if not authorization or not authorization.startswith('Bearer '):
        raise HTTPException(401, 'Token required')

    token = authorization.split(' ')[1]
    try:
        payload = jwt.decode(
            token, PUBLIC_KEY,
            algorithms=['RS256'],
            options={'verify_aud': False}
        )
        # payload содержит:
        # - sub: user_id (Passport ставит)
        # - exp, iat, nbf, jti
        return payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(401, 'Token expired')
    except jwt.InvalidTokenError as e:
        raise HTTPException(401, f'Invalid token: {e}')
