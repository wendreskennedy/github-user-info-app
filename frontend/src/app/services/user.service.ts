import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class UserService {
  private baseUrl = '/api';

  constructor(private http: HttpClient) {}

  getUser(username: string) {
    return this.http.get(`${this.baseUrl}/${username}`);
  }

  getFollowings(username: string) {
    return this.http.get(`${this.baseUrl}/${username}/followings`);
  }
}
