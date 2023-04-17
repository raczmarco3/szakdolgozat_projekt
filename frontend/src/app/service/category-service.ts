import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import {ProductPagination} from "../model/product-pagination";
import {Category} from "../model/category";

@Injectable({
  providedIn: 'root'
})
export class CategoryService
{
  private baseUrl = 'http://localhost:8000/api/category';

  constructor(private http: HttpClient) { }

  getCategories() {
    return this.http.get<Category[]>(`${this.baseUrl}` + '/all');
  }
}
