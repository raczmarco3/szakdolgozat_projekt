import { Component } from '@angular/core';
import {Product} from "../model/product";
import {MainPageService} from "../service/main-page-service";

@Component({
  selector: 'app-main-page',
  templateUrl: './main-page.component.html',
  styleUrls: ['./main-page.component.css']
})
export class MainPageComponent {
  data: Product[] = [];
  errorMsg: any;
  errorStatus: number;
  pagination: number = 1;
  allProducts: number;
  public arr: number[] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

  constructor(private mainPageService: MainPageService) { }

  ngOnInit() {
    this.getData();
  }

  getData() {
    this.mainPageService.mainPageShowProducts(this.pagination).subscribe(
      {
        next: (response) => {
          this.data = response.products;
          this.allProducts = response.totalProducts;
          this.errorMsg = undefined;
        },
        error: (msg) => {
          this.errorMsg = msg.error.msg;
          this.errorStatus = msg.status;
          this.data = [];
        }
      }
    );
  }

  renderPage(event: number) {
    this.pagination = event;
    this.getData();
  }

}
