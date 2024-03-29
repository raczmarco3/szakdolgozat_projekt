import {Component, ViewChild} from '@angular/core';
import {LoginService} from "../../../service/login-service";
import {Category} from "../../admin-model/category";
import {MatTableDataSource} from "@angular/material/table";
import {MatSort} from "@angular/material/sort";
import {MatPaginator} from "@angular/material/paginator";
import {CategoryService} from "../../admin-service/category-service";
import {MatDialog} from "@angular/material/dialog";
import {AddCategoryComponent} from "../add-category/add-category.component";
import {EditCategoryComponent} from "../edit-category/edit-category.component";

@Component({
  selector: 'app-list-category',
  templateUrl: './list-category.component.html',
  styleUrls: ['./list-category.component.css']
})
export class ListCategoryComponent {
  loggedIn: boolean = false;
  isAdmin: boolean = false;
  data: Category[] = [];
  dataSource = new MatTableDataSource<Category>();
  displayedColumns: string[] = [
    'id',
    'name',
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
  constructor(private loginService: LoginService, private categoryService: CategoryService,
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
    this.categoryService.getCategories().subscribe(
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
  addCategory() {
    if(this.editOpened) {
      this.editDialog.close();
    }
    if(!this.addOpened) {
      this.addOpened = true;
      this.addDialog = this.dialog.open(AddCategoryComponent,
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
  deleteCategory(event: any) {
    const id = event.srcElement.attributes.id.nodeValue;
    if(confirm("Are you sure you want to delete this?")) {
      this.categoryService.deleteCategory(id).subscribe(
        {
          next: () =>
          {
            this.getData()
          },
          error: (msg) => {
            this.msg = msg.error.msg;
          }
        }
      )
    }
  }
  editCategory(event: any) {
    if(this.addOpened) {
      this.addDialog.close();
    }
    if(!this.editOpened) {
      this.editOpened = true;
      const id = event.srcElement.attributes.id.nodeValue;
      const editedData = this.data.find(data => data.id == id);
      this.editDialog = this.dialog.open(EditCategoryComponent,
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
