import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { Title } from '@angular/platform-browser';
import { ActivatedRoute } from '@angular/router';

interface response1 {
  data: any;
}

@Component({
  selector: 'app-product-index',
  templateUrl: './product-index.component.html',
  styleUrls: ['./product-index.component.css']
})
export class ProductIndexComponent implements OnInit {

  product_list: response1;
  product_list1: response1;
  baseurl: any;
  pageTitle: string;
  currentPageNo: string;

  constructor(
    private titleService: Title,
    private backendapiService: BackendapiService
  ) { }

  ngOnInit() {
    this.backendapiService.getProducts('getProducts').subscribe(res => {
      this.product_list1 = res.data;
      this.product_list = this.product_list1.data;
      this.currentPageNo = res.data.current_page;
    });
    this.baseurl = this.backendapiService.getBaseUrl();
    this.pageTitle = this.backendapiService.getSiteName();
    this.titleService.setTitle('Products | ' + this.pageTitle);
    window.scrollTo(0, 0);
  }

}
