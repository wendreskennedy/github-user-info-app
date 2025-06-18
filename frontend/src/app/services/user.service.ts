import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class UserService {
  private baseUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  getUser(username: string) {
    return this.http.get(`${this.baseUrl}/${username}`);
  }

  getFollowings(username: string): Observable<any[]> {
    return this.http.get<any[]>(`${this.baseUrl}/${username}/followings`);
  }
}
