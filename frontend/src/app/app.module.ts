import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { MainPageComponent } from './main-page/main-page.component';
import { MenuComponent } from './menu/menu.component';
import { HttpClientModule } from '@angular/common/http';
import { NgxPaginationModule } from 'ngx-pagination';
import { RegistrationComponent } from './registration/registration.component';
import {ReactiveFormsModule} from "@angular/forms";
import {MatDialogModule} from '@angular/material/dialog';
import { LoginComponent } from './login/login.component';
import { AdminMenuComponent } from './admin/admin-menu/admin-menu.component';
import { ListCategoryComponent } from './admin/category/list-category/list-category.component';
import { AddCategoryComponent } from './admin/category/add-category/add-category.component';
import { EditCategoryComponent } from './admin/category/edit-category/edit-category.component';
import { MatTableModule } from '@angular/material/table';
import {MatSortModule} from "@angular/material/sort";
import {MatPaginatorModule} from "@angular/material/paginator";
import { ListMethodComponent } from './admin/method/list-method/list-method.component';
import { AddMethodComponent } from './admin/method/add-method/add-method.component';
import { EditMethodComponent } from './admin/method/edit-method/edit-method.component';
import { ListStatusComponent } from './admin/status/list-status/list-status.component';
import { AddStatusComponent } from './admin/status/add-status/add-status.component';
import { EditStatusComponent } from './admin/status/edit-status/edit-status.component';
import { ListProductComponent } from './admin/product/list-product/list-product.component';
import { AddProductComponent } from './admin/product/add-product/add-product.component';
import { EditProductComponent } from './admin/product/edit-product/edit-product.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

@NgModule({
  declarations: [
    AppComponent,
    MainPageComponent,
    MenuComponent,
    RegistrationComponent,
    LoginComponent,
    AdminMenuComponent,
    ListCategoryComponent,
    AddCategoryComponent,
    EditCategoryComponent,
    ListMethodComponent,
    AddMethodComponent,
    EditMethodComponent,
    ListStatusComponent,
    AddStatusComponent,
    EditStatusComponent,
    ListProductComponent,
    AddProductComponent,
    EditProductComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    NgxPaginationModule,
    ReactiveFormsModule,
    MatDialogModule,
    MatTableModule,
    MatSortModule,
    MatPaginatorModule,
    BrowserAnimationsModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
