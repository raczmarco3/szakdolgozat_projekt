import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Status} from "../admin-model/status";

@Injectable({
  providedIn: 'root'
})
export class StatusService
{
  private baseUrl = 'http://localhost:8000/api/status/';

  constructor(private http: HttpClient) { }

  getStatuses()
  {
    return this.http.get<Status[]>(`${this.baseUrl}`+'get');
  }

  addStatus(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'add', jsonContent, {headers: headers, withCredentials: true});
  }

  deleteStatus(id: number)
  {
    return this.http.delete(`${this.baseUrl}`+'delete/'+id, {withCredentials: true});
  }

  editStatus(id: number, jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.put(`${this.baseUrl}`+'edit/'+id, jsonContent, {headers: headers, withCredentials: true});
  }
}
