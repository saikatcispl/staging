import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { Title } from '@angular/platform-browser';


interface response1 {
    data: any;
}

@Component({
    selector: 'app-lander',
    templateUrl: './product.component.html',
    styleUrls: ['./product.component.css']
})
export class ProductComponent implements OnInit {

    product_list: response1;
    product_list1: response1;
    baseurl: any;
    pageTitle:string;
    currentPageNo:string;

    public constructor(
        private titleService: Title,
        private backendapiService: BackendapiService
    ) { }

    ngOnInit() {
        this.backendapiService.getProducts('getProducts?page=1&media=1&limit=4').subscribe(res => {
            this.product_list1 = res.data;
            this.product_list = this.product_list1.data;
            this.currentPageNo = res.data.current_page;
        });
        this.baseurl = this.backendapiService.getBaseUrl();
        this.pageTitle = this.backendapiService.getSiteName();
        this.titleService.setTitle('Products | '+this.pageTitle);
    }
}

