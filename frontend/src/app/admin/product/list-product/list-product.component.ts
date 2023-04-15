import {Component, ViewChild} from '@angular/core';
import {Method} from "../../admin-model/method";
import {MatTableDataSource} from "@angular/material/table";
import {MatSort} from "@angular/material/sort";
import {MatPaginator} from "@angular/material/paginator";
import {LoginService} from "../../../service/login-service";
import {MatDialog} from "@angular/material/dialog";
import {AddMethodComponent} from "../../method/add-method/add-method.component";
import {EditMethodComponent} from "../../method/edit-method/edit-method.component";
import {ProductService} from "../../admin-service/product-service";
import {AddProductComponent} from "../add-product/add-product.component";
import {EditProductComponent} from "../edit-product/edit-product.component";

@Component({
  selector: 'app-list-product',
  templateUrl: './list-product.component.html',
  styleUrls: ['./list-product.component.css']
})
export class ListProductComponent {
  loggedIn: boolean = false;
  isAdmin: boolean = false;
  data: Method[] = [];
  dataSource = new MatTableDataSource<Method>();
  displayedColumns: string[] = [
    'id',
    'name',
    'price',
    'category',
    'createdAt',
    'updatedAt',
    'image',
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
  constructor(private loginService: LoginService, private productService: ProductService,
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
    this.productService.getProducts().subscribe(
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
  deleteProduct(event: any) {
    const id = event.srcElement.attributes.id.nodeValue;
    if(confirm("Are you sure you want to delete this?")) {
      this.productService.deleteProduct(id).subscribe(
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
  addProduct() {
    const dialogRef = this.dialog.open(AddProductComponent,
      {height: '400px', width: '600px'}, );
    dialogRef.afterClosed().subscribe(
      {
        next: () =>
        {
          this.msg = "";
          this.getData();
        }
      }
    );
  }
  editProduct(event: any) {
    const id = event.srcElement.attributes.id.nodeValue;
    const editedData = this.data.find(data => data.id == id);
    const dialogRef = this.dialog.open(EditProductComponent,
      {height: '400px', width: '600px', data: editedData}, );
    dialogRef.afterClosed().subscribe(
      {
        next: () =>
        {
          this.getData();
        }
      }
    );
  }
}
