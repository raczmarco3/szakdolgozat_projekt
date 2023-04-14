import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {MainPageComponent} from "./main-page/main-page.component";
import {RegistrationComponent} from "./registration/registration.component";
import {IndexComponent} from "./admin/index/index.component";
import {ListCategoryComponent} from "./admin/category/list-category/list-category.component";

const routes: Routes = [
  { path: '', component: MainPageComponent },
  { path: "register", component: RegistrationComponent },
  { path: "admin", component: IndexComponent},
  { path: "admin/category", component: ListCategoryComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
