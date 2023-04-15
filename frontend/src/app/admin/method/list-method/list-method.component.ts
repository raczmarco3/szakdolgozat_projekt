import {Component, ViewChild} from '@angular/core';
import {MatTableDataSource} from "@angular/material/table";
import {MatSort} from "@angular/material/sort";
import {MatPaginator} from "@angular/material/paginator";
import {LoginService} from "../../../service/login-service";
import {MatDialog} from "@angular/material/dialog";
import {Method} from "../../admin-model/method";
import {MethodService} from "../../admin-service/method-service";

@Component({
  selector: 'app-list-method',
  templateUrl: './list-method.component.html',
  styleUrls: ['./list-method.component.css']
})
export class ListMethodComponent {
  loggedIn: boolean = false;
  isAdmin: boolean = false;
  data: Method[] = [];
  dataSource = new MatTableDataSource<Method>();
  displayedColumns: string[] = [
    'id',
    'name',
    'userId',
    'action'
  ];
  msg: string;

  @ViewChild(MatSort, { static: false })
  set sort(v: MatSort) {
    this.dataSource.sort = v;
  }
  @ViewChild(MatPaginator, { static: false })
  set paginator(v: MatPaginator) {
    this.dataSource.paginator = v;
  }
  constructor(private loginService: LoginService, private methodService: MethodService,
              public dialog: MatDialog) { }

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
    this.methodService.getMethods().subscribe(
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

}
