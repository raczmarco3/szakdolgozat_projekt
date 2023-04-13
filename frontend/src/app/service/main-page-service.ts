import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {Product} from "../model/product";

@Injectable({
  providedIn: 'root'
})
export class MainPageService
{
  private baseUrl = 'http://localhost:8000/api/';

  constructor(private http: HttpClient) { }

  mainPageShowProducts() {
    return this.http.get<Product[]>(`${this.baseUrl}` + 'product/get/all');
  }
}
