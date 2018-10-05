import { Component, OnInit } from '@angular/core';
import { BackendapiService } from '../backendapi.service';
import { ActivatedRoute } from '@angular/router';
import { Title } from '@angular/platform-browser';
import '../../assets/js/jquery.responsive.tabs.min.js';
import '../../assets/js/pagelevel/responsivetabs.js';

interface response {
  data: any;
}

@Component({
  selector: 'app-product-details',
  templateUrl: './product-details.component.html',
  styleUrls: ['./product-details.component.css']
})
export class ProductDetailsComponent implements OnInit {

  product_details: any;
  baseurl: any;
  productId: string;
  pageTitle: string;
  membership_uemail: string = '';
  authenticated: any = false;

  constructor(
    private titleService: Title,
    private route: ActivatedRoute,
    private backendapiService: BackendapiService
  ) { }

  ngOnInit() {
    this.membership_uemail = localStorage.getItem("membership_uemail");
    if (this.membership_uemail) {
      this.authenticated = true;
    }
    this.productId = this.route.snapshot.paramMap.get('id');
    this.backendapiService.getProduct('getProduct', this.productId).subscribe(res => {
      this.product_details = res.data;
    });
    this.baseurl = this.backendapiService.getBaseUrl();
    this.pageTitle = this.backendapiService.getSiteName();
    // this.titleService.setTitle(this.product_details.product_name +' | '+ this.pageTitle);
  }

}
