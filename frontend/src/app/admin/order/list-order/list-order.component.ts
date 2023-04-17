import {Component, ViewChild} from '@angular/core';
import {Method} from "../../admin-model/method";
import {MatTableDataSource} from "@angular/material/table";
import {MatSort} from "@angular/material/sort";
import {MatPaginator} from "@angular/material/paginator";
import {LoginService} from "../../../service/login-service";
import {OrderService} from "../../admin-service/order-service";
import {Order} from "../../../model/order";

@Component({
  selector: 'app-list-order',
  templateUrl: './list-order.component.html',
  styleUrls: ['./list-order.component.css']
})
export class ListOrderComponent {
  loggedIn: boolean = false;
  isAdmin: boolean = false;
  data: Order[] = [];
  dataSource = new MatTableDataSource<Order>();
  displayedColumns: string[] = [
    'id',
    'products',
    'address',
    'createdAt',
    'status',
    'method',
    'action'
  ];
  msg: string;
  jsonContent: JSON;
  obj: any;

  @ViewChild(MatSort, { static: false })
  set sort(v: MatSort) {
    this.dataSource.sort = v;
  }
  @ViewChild(MatPaginator, { static: false })
  set paginator(v: MatPaginator) {
    this.dataSource.paginator = v;
  }
  constructor(private loginService: LoginService, private orderService: OrderService) { }

  ngOnInit() {
    if(this.loginService.getData("loggedIn") == "true") {
      this.loggedIn = true;
    }
    if(this.loginService.getData("role") == "ROLE_ADMIN") {
      this.isAdmin = true;
    }
    this.getData();
  }
  getData() {
    this.orderService.getOrder().subscribe(
      {
        next: (response) => {
          this.data = response;
          this.dataSource = new MatTableDataSource(this.data);
          this.dataSource.sort = this.sort;
          this.dataSource.paginator = this.paginator;
        },
        error: (msg) => {
          this.msg = msg.error.msg;
          this.data = [];
          this.dataSource = new MatTableDataSource(this.data);
        }
      }
    );
  }
  onChange(event: any, orderId: number) {
    this.obj = {
      "order_id": orderId,
      'statusId': parseInt(String(event.target.value))
    };

    this.jsonContent = <JSON>this.obj;

    this.orderService.changeOrderStatus(this.jsonContent).subscribe(
      {
        next: (response) => {
          this.getData();
        }
      }
    );
  }
}
