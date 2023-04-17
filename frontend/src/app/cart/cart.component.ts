import { Component } from '@angular/core';
import {CartService} from "../service/cart-service";
import {LoginService} from "../service/login-service";
import {Cart} from "../model/cart";

@Component({
  selector: 'app-cart',
  templateUrl: './cart.component.html',
  styleUrls: ['./cart.component.css']
})
export class CartComponent {
  msg: string;
  loggedIn: boolean = false;
  data: Cart;
  jsonContent: JSON;
  obj: any;

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

}
