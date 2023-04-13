import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {ProductPagination} from "../model/product-pagination";

@Injectable({
  providedIn: 'root'
})
export class MainPageService
{
  private baseUrl = 'http://localhost:8000/api/';

  constructor(private http: HttpClient) { }

  mainPageShowProducts(page: number) {
    return this.http.get<ProductPagination>(`${this.baseUrl}` + 'product/page/' +page);
  }
}
