const ACCESS_TOKEN_KEY = 'auth.access_token';
const REFRESH_TOKEN_KEY = 'auth.refresh_token';

class Storage {
  constructor(type = 'localStorage') {
    this.store = window[type];
  }

  get(key) {
    return this.store.getItem(key);
  }

  set(key, value) {
    return this.store.setItem(key, value);
  }

  remove(key) {
    this.store.removeItem(key);
  }

  getAccessToken() {
    return this.get(ACCESS_TOKEN_KEY);
  }

  setAccessToken(token) {
    return this.set(ACCESS_TOKEN_KEY, token);
  }

  hasAccessToken() {
    return !!this.get(ACCESS_TOKEN_KEY);
  }

  removeAccessToken() {
    return this.remove(ACCESS_TOKEN_KEY);
  }

  getRefreshToken() {
    return this.get(REFRESH_TOKEN_KEY);
  }

  setRefreshToken(token) {
    return this.set(REFRESH_TOKEN_KEY, token);
  }

  hasRefreshToken() {
    return !!this.get(REFRESH_TOKEN_KEY);
  }

  removeRefreshToken() {
    return this.remove(REFRESH_TOKEN_KEY);
  }
}

let storage = new Storage();

const isLocalStorageAccessible = () => {
  try {
    storage.set('test-local-storage', 1);
    storage.remove('test-local-storage');
    return true;
  } catch (e) {
    return false;
  }
};

if (!isLocalStorageAccessible()) {
  storage = new Storage('sessionStorage');
}

export default storage;
