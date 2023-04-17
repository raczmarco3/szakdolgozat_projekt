import { Component } from '@angular/core';
import {CartService} from "../service/cart-service";
import {LoginService} from "../service/login-service";
import {Cart} from "../model/cart";
import {FormControl, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-cart',
  templateUrl: './cart.component.html',
  styleUrls: ['./cart.component.css']
})
export class CartComponent {
  msg: string;
  loggedIn: boolean = false;
  data: Cart = new Cart();
  jsonContent: JSON;
  obj: any;
  orderedProducts: number[] = [];

  constructor(private cartService: CartService, private loginService: LoginService) { }

  ngOnInit() {
    if(this.loginService.getData("loggedIn") == "true") {
      this.loggedIn = true;
    }
    this.getData();
  }
  getData() {
    this.cartService.getCart().subscribe(
      {
        next: (response) => {
          this.data = response;
        },
        error: (msg) => {
          this.msg = msg.error.msg;
        }
      }
    );
  }

  removeFromCart(id: number) {
    this.obj = {
      "product_id": id,
    };

    this.jsonContent = <JSON>this.obj;

    this.cartService.removeFromCart(this.jsonContent).subscribe(
      {
        next: (response) => {
          window.location.reload();
        }
      }
    );
  }
  orderForm = new FormGroup({
    address: new FormControl(),
    method: new FormControl()
  });
  onSubmit() {
    this.data.products.forEach((value) => {
      this.orderedProducts.push(value.id);
    });

    this.obj = {
      "products": this.orderedProducts,
      "method_id": parseInt(this.orderForm.get('method')?.value),
      "address": this.orderForm.get('address')?.value,
    }
    this.jsonContent = <JSON>this.obj;

    this.cartService.orderProducts(this.jsonContent).subscribe(
      {
        next: (response) => {
          this.msg = "Sikeres rendelés!";
          this.data = new Cart();
        }
      }
    );
  }

}
