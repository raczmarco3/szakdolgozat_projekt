import {Component, ViewChild} from '@angular/core';
import {MatTableDataSource} from "@angular/material/table";
import {MatSort} from "@angular/material/sort";
import {MatPaginator} from "@angular/material/paginator";
import {LoginService} from "../../../service/login-service";
import {MatDialog} from "@angular/material/dialog";
import {Method} from "../../admin-model/method";
import {MethodService} from "../../admin-service/method-service";
import {AddCategoryComponent} from "../../category/add-category/add-category.component";
import {AddMethodComponent} from "../add-method/add-method.component";
import {EditCategoryComponent} from "../../category/edit-category/edit-category.component";
import {EditMethodComponent} from "../edit-method/edit-method.component";

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
  addDialog: any;
  editDialog: any;
  editOpened: boolean = false;
  addOpened: boolean = false;

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
  deleteMethod(event: any) {
    const id = event.srcElement.attributes.id.nodeValue;
    if(confirm("Are you sure you want to delete this?")) {
      this.methodService.deleteMethod(id).subscribe(
        {
          next: () =>
          {
            this.getData()
          },
          error: (msg) => {
            this.msg = msg.error.msg;
          }
        }
      );
    }
  }
  addMethod() {
    if(this.editOpened) {
      this.editDialog.close();
    }
    if(!this.addOpened) {
      this.addOpened = true;
      this.addDialog = this.dialog.open(AddMethodComponent,
        {height: '200px', width: '600px'},);
      this.addDialog.afterClosed().subscribe(
        {
          next: () => {
            this.msg = "";
            this.getData();
            this.addOpened = false;
          }
        }
      );
    }
  }
  editMethod(event: any) {
    if(this.addOpened) {
      this.addDialog.close();
    }
    if(!this.editOpened) {
      this.editOpened = true;
      const id = event.srcElement.attributes.id.nodeValue;
      const editedData = this.data.find(data => data.id == id);
      this.editDialog = this.dialog.open(EditMethodComponent,
        {height: '200px', width: '600px', data: editedData},);
      this.editDialog.afterClosed().subscribe(
        {
          next: () => {
            this.getData();
            this.editOpened = false;
          }
        }
      );
    }
  }
}
