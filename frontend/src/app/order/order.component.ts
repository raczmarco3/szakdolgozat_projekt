import { Component } from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ProductPageService} from "../service/product-service";
import {FormControl, FormGroup} from "@angular/forms";
import {OrderService} from "../service/order-service";
import {Order} from "../model/order";

@Component({
  selector: 'app-order',
  templateUrl: './order.component.html',
  styleUrls: ['./order.component.css']
})
export class OrderComponent {
  data: Order[];
  msg: string;
  constructor(private orderService: OrderService) { }

  rateForm = new FormGroup({
    rate: new FormControl(),
  });
  ngOnInit() {
    this.getData();
  }
  getData() {
    this.orderService.getOrder().subscribe(
      {
        next: (response) => {
          this.data = response;
          console.log(this.data);
        },
        error: (msg) => {
          this.msg = msg.error.msg;
        }
      }
    );
  }

}
