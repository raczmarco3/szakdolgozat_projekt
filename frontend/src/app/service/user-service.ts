import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {User} from "../model/user";

@Injectable({
  providedIn: 'root'
})
export class UserService
{
  private baseUrl = 'http://localhost:8000/api/';

  constructor(private http: HttpClient) { }

  login(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post<User>(`${this.baseUrl}`+'login', jsonContent, {headers: headers, withCredentials: true});
  }

  logout() {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.get(`${this.baseUrl}`+'logout', {headers: headers, withCredentials: true});
  }
}
