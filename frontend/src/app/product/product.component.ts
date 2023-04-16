import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import {Subscription} from "rxjs";
import {Product} from "../model/product";
import {ProductPageService} from "../service/product-service";
import {FormControl, FormGroup} from "@angular/forms";

@Component({
  selector: 'app-product',
  templateUrl: './product.component.html',
  styleUrls: ['./product.component.css']
})
export class ProductComponent {
  id: number;
  private routeSub: Subscription;
  product: Product;
  msg: string;
  ertekMsg: string;
  jsonContent: JSON;
  obj: any;
  nrSelect: number = 1;

  constructor(private route: ActivatedRoute, private productService: ProductPageService) { }

  rateForm = new FormGroup({
    rate: new FormControl(),
  });
  ngOnInit() {
    this.routeSub = this.route.params.subscribe(params => {
      this.id = params['id']
    });

    this.getData();
  }
  getData() {
    this.productService.getProduct(this.id).subscribe(
      {
        next: (response) => {
          this.product = response;
        },
        error: (msg) => {
          this.msg = msg.error.msg;
        }
      }
    );
  }
  onSubmit(event: any) {
    this.obj = {
      "productId": parseInt(String(this.id)),
      "rating": parseInt(String(this.rateForm.get('rate')?.value)),
    };

    this.jsonContent = <JSON>this.obj;
    console.log(this.jsonContent);

    this.productService.rateProduct(this.jsonContent).subscribe(
      {
        next: (response) => {
          this.ertekMsg = "Köszönjük az érétkelést!";
          this.getData();
        },
        error: (msg) => {
          this.msg = msg.error.msg;
        }
      }
    );
  }


}
