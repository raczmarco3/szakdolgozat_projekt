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

  constructor(private mainPageService: MainPageService) { }

  ngOnInit() {
    this.getData();
  }

  getData() {
    this.mainPageService.mainPageShowProducts().subscribe(
      {
        next: (response) => {
          this.data = response;
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

}
