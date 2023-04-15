import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Category} from "../admin-model/category";

@Injectable({
  providedIn: 'root'
})
export class CategoryService
{
  private baseUrl = 'http://localhost:8000/api/category/';

  constructor(private http: HttpClient) { }

  getCategories()
  {
    return this.http.get<Category[]>(`${this.baseUrl}`+'all');
  }

  addCategory(jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.post(`${this.baseUrl}`+'add', jsonContent, {headers: headers, withCredentials: true});
  }

  deleteCategory(id: number)
  {
    return this.http.delete(`${this.baseUrl}`+'delete/'+id, {withCredentials: true});
  }

  editCategory(id: number, jsonContent: JSON)
  {
    let headers = new HttpHeaders();
    headers = headers.set('Accept', 'application/json');
    return this.http.put(`${this.baseUrl}`+'edit/'+id, jsonContent, {headers: headers, withCredentials: true});
  }
}
