import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class RegistrationService
{
  private baseUrl = 'http://localhost:8000/api/user/';

  constructor(private http: HttpClient) { }

  register(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'register', jsonContent, {headers: headers});
  }
}
