import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Method} from "../admin-model/method";

@Injectable({
  providedIn: 'root'
})
export class MethodService
{
  private baseUrl = 'http://localhost:8000/api/method/';

  constructor(private http: HttpClient) { }

  getMethods()
  {
    return this.http.get<Method[]>(`${this.baseUrl}`+'all');
  }

  addMethod(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'add', jsonContent, {headers: headers, withCredentials: true});
  }

  deleteMethod(id: number)
  {
    return this.http.delete(`${this.baseUrl}`+'delete/'+id, {withCredentials: true});
  }

  editMethod(id: number, jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.put(`${this.baseUrl}`+'edit/'+id, jsonContent, {headers: headers, withCredentials: true});
  }
}
