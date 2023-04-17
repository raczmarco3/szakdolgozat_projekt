import { Component } from '@angular/core';
import {Product} from "../model/product";
import {MainPageService} from "../service/main-page-service";
import {CartService} from "../service/cart-service";
import {LoginService} from "../service/login-service";

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
  jsonContent: JSON;
  obj: any;
  cartMsg: string;

  constructor(private mainPageService: MainPageService, private cartService: CartService,
              private loginService: LoginService) { }

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
  addToCart(id: number) {
    this.obj = {
      "product_id": id,
    };

    this.jsonContent = <JSON>this.obj;

    this.cartService.addToCart(this.jsonContent).subscribe(
      {
        next: (response) => {
          window.location.reload();
        },
        error: (msg) => {
          this.cartMsg = "A kosár használatához be kell jelentkezned!";
        }
      }
    );
  }
}
