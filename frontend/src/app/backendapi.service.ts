import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject } from 'rxjs';
import { HttpHeaders } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})




export class BackendapiService {

  API_URL = "http://member.localhost.com/api/";
  BASE_URL = "http://member.localhost.com/";
  SITENAME = 'Membership';
  httpOptions = {
    headers: new HttpHeaders({
      'Content-Type': 'application/json',
      'Access-Control-Allow-Origin': '*'
    })
  };

  private dataSource = new BehaviorSubject('');
  currentMessage = this.dataSource.asObservable();

  constructor(private httpClient: HttpClient) { }

  getSiteName(): any {
    return this.SITENAME;
  }

  getCountryList(requiredCountries = ''): any {
    if(requiredCountries !== ''){
      return this.httpClient.get(this.API_URL + 'getCountryList?id='+requiredCountries);
    }else{
      return this.httpClient.get(this.API_URL + 'getCountryList');
    }
    
  }

  getStateList(countryCode): any {
    return this.httpClient.get(this.API_URL + 'getStateList?id='+countryCode);
  }

  getProducts(method, params = ''): any {
    return this.httpClient.get(this.API_URL + method);
  }

  getProduct(method, id): any {
    return this.httpClient.get(this.API_URL + method + '?id=' + id);
  }

  getCms(method, slug): any {
    return this.httpClient.get(this.API_URL + method + '?slug=' + slug);
  }

  getSettings(method, params = ''): any {
    return this.httpClient.get(this.API_URL + method);
  }

  getBaseUrl(): any {
    return this.BASE_URL;
  }

  updateData(message: any) {
    this.dataSource.next(message);
  }

  postContactForm(method, model): any {
    return this.httpClient.post(this.API_URL + method, model, this.httpOptions);
  }

  postLoginForm(method, model): any {
    return this.httpClient.post(this.API_URL + method, model, this.httpOptions);
  }

  postSigninForm(method, model): any {
    return this.httpClient.post(this.API_URL + method, model, this.httpOptions);
  }

}
