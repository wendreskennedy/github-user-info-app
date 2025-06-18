import { Component } from '@angular/core';
import { SearchComponent } from './components/search/search.component';
import { UserCardComponent } from './components/user-card/user-card.component';
import { NgFor, NgIf } from '@angular/common';
import { UserService } from './services/user.service';
import { FollowingsComponent } from './components/followings/followings.component';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    SearchComponent,
    UserCardComponent,
    FollowingsComponent,
    NgIf,
    NgFor,
    FormsModule,
  ],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent {
  title = 'github-user-info-app';
  user: any = null;
  followings: any[] = [];

  constructor(private userService: UserService) {}

  onSearch(username: string) {
    this.userService.getUser(username).subscribe((data) => {
      this.user = data;
    });

    this.userService.getFollowings(username).subscribe((data) => {
      this.followings = data;
    });
  }
}
