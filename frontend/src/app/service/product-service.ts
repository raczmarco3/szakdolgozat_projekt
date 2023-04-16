import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {ProductPagination} from "../model/product-pagination";
import {Product} from "../model/product";

@Injectable({
  providedIn: 'root'
})
export class ProductPageService
{
  private baseUrl = 'http://localhost:8000/api/';

  constructor(private http: HttpClient) { }

  getProduct(id: number) {
    return this.http.get<Product>(`${this.baseUrl}` + 'product/get/' +id);
  }

  rateProduct(jsonContent: JSON) {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}` + `rate/add`, jsonContent, {headers: headers, withCredentials: true});
  }

  getRelatedProducts(categoryName: string, productId: number) {
    return this.http.get<Product[]>(`${this.baseUrl}` + 'category/related/' +categoryName + "/"+productId);
  }
}
