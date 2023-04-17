import { Component } from '@angular/core';
import {MainPageService} from "../service/main-page-service";
import {Category} from "../model/category";
import {CategoryService} from "../service/category-service";

@Component({
  selector: 'app-category',
  templateUrl: './category.component.html',
  styleUrls: ['./category.component.css']
})
export class CategoryComponent {
  data: Category[] = [];
  errorMsg: any;

  constructor(private categoryService: CategoryService) { }

  ngOnInit() {
    this.getData();
  }

  getData() {
    this.categoryService.getCategories().subscribe(
      {
        next: (response) => {
          this.data = response;
        },
        error: (msg) => {
          this.errorMsg = msg.error.msg;
        }
      }
    );
  }

}
