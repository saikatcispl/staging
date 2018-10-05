import { BrowserModule, Title } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule }    from '@angular/common/http';
import { BackendapiService } from './backendapi.service';
import { FormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { NavbarComponent } from './navbar/navbar.component';
import { FooterComponent } from './footer/footer.component';
import { ProductComponent } from './product/product.component';
import { LandingPageContentComponent } from './landing-page-content/landing-page-content.component';
import { AppRoutingModule } from './app-routing.module';
import { LimitToPipe } from './limit-to.pipe';
import { ProductDetailsComponent } from './product-details/product-details.component';
import { PagerComponent } from './pager/pager.component';
import { ErrorComponent } from './error/error.component';
import { CmsComponent } from './cms/cms.component';
import { ContactusComponent } from './contactus/contactus.component';
import { ProductIndexComponent } from './product-index/product-index.component';
import { NewsLetterComponent } from './news-letter/news-letter.component';
import { LoginComponent } from './login/login.component';
import { MembershipComponent } from './membership/membership.component';
import { SignupComponent } from './signup/signup.component';
import { MembershipDetailsComponent } from './membership-details/membership-details.component';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    FooterComponent,
    ProductComponent,
    LandingPageContentComponent,
    LimitToPipe,
    ProductDetailsComponent,
    PagerComponent,
    ErrorComponent,
    CmsComponent,
    ContactusComponent,
    ProductIndexComponent,
    NewsLetterComponent,
    LoginComponent,
    MembershipComponent,
    SignupComponent,
    MembershipDetailsComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule
  ],
  providers: [BackendapiService, Title],
  bootstrap: [AppComponent]
})
export class AppModule { }
