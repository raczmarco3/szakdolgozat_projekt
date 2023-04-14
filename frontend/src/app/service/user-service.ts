import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';

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
    return this.http.post(`${this.baseUrl}`+'login', jsonContent, {headers: headers});
  }
}
