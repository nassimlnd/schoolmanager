import { Injectable, UnauthorizedException } from '@nestjs/common';

@Injectable()
export class KordisAuthService {
  private static readonly OAUTH_AUTHORIZE_URL =
    'https://authentication.kordis.fr/oauth/authorize?client_id={clientId}&response_type=token';

  private static readonly DEFAULT_CLIENT_ID = 'skolae-app';

  async authenticate(
    login: string,
    password: string,
    clientId = KordisAuthService.DEFAULT_CLIENT_ID,
  ): Promise<string> {
    const url = KordisAuthService.OAUTH_AUTHORIZE_URL.replace(
      '{clientId}',
      clientId,
    );

    const response = await fetch(url, {
      method: 'GET',
      headers: {
        Authorization:
          'Basic ' + Buffer.from(`${login}:${password}`).toString('base64'),
      },
      redirect: 'manual',
    });

    if (response.status === 401) {
      throw new UnauthorizedException('Wrong credentials');
    }

    const location = response.headers.get('location');
    if (!location) {
      throw new Error('No redirect location in response');
    }

    return this.extractAccessToken(location);
  }

  private extractAccessToken(location: string): string {
    const url = new URL(location);
    const fragment = url.hash.startsWith('#') ? url.hash.slice(1) : url.hash;

    if (!fragment) {
      throw new Error('No fragment in redirect URL');
    }

    const params = new URLSearchParams(fragment);
    const accessToken = params.get('access_token');

    if (!accessToken) {
      throw new Error('No access_token in redirect fragment');
    }

    return accessToken;
  }

  static encodeCredentials(login: string, password: string): string {
    return Buffer.from(`${login}:${password}`).toString('base64');
  }

  static decodeCredentials(token: string): { login: string; password: string } {
    const decoded = Buffer.from(token, 'base64').toString('utf-8');
    const [login, password] = decoded.split(':');
    return { login, password };
  }
}
