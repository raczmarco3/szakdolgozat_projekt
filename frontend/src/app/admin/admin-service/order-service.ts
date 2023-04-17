import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Order} from "../../model/order";

@Injectable({
  providedIn: 'root'
})
export class OrderService
{
  private baseUrl = 'http://localhost:8000/api/order';

  constructor(private http: HttpClient) { }

  getOrder() {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.get<Order[]>(`${this.baseUrl}` + '/get', {headers: headers, withCredentials: true});
  }

  changeOrderStatus(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.put(`${this.baseUrl}`+'/status', jsonContent, {headers: headers, withCredentials: true});
  }
}
