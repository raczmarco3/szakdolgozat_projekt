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

@NgModule({
  declarations: [
    AppComponent,
    MainPageComponent,
    MenuComponent,
    RegistrationComponent,
    LoginComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    NgxPaginationModule,
    ReactiveFormsModule,
    MatDialogModule,
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
