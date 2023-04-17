import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Cart} from "../model/cart";
import {Msg} from "../model/msg";

@Injectable({
  providedIn: 'root'
})
export class CartService
{
  private baseUrl = 'http://localhost:8000/api/cart/';

  constructor(private http: HttpClient) { }

  getCart()
  {
    return this.http.get<Cart>(`${this.baseUrl}`+'get', {withCredentials: true});
  }

  addToCart(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'add', jsonContent, {headers: headers, withCredentials: true});
  }

  removeFromCart(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'remove', jsonContent, {headers: headers, withCredentials: true});
  }

  getProductNumber()
  {
    return this.http.get<Msg>(`${this.baseUrl}`+'productnumber', {withCredentials: true});
  }

  orderProducts(jsonContent: JSON) {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post('http://localhost:8000/api/order/add', jsonContent, {headers: headers, withCredentials: true});
  }
}
