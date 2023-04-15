import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Product} from "../admin-model/product";

@Injectable({
  providedIn: 'root'
})
export class ProductService
{
  private baseUrl = 'http://localhost:8000/api/product/';

  constructor(private http: HttpClient) { }

  getProducts()
  {
    return this.http.get<Product[]>(`${this.baseUrl}`+'all');
  }

  addProduct(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'add', jsonContent, {headers: headers, withCredentials: true});
  }

  deleteProduct(id: number)
  {
    return this.http.delete(`${this.baseUrl}`+'delete/'+id, {withCredentials: true});
  }

  editProduct(id: number, jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.put(`${this.baseUrl}`+'edit/'+id, jsonContent, {headers: headers, withCredentials: true});
  }
}
