import { Component } from '@angular/core';
import { SearchComponent } from './components/search/search.component';
import { UserCardComponent } from './components/user-card/user-card.component';
import { UserNotFoundComponent } from './components/user-not-found/user-not-found.component';
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
    UserNotFoundComponent,
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
  userNotFound: boolean = false;
  searchedUsername: string = '';

  constructor(private userService: UserService) {}

  onSearch(username: string) {
    this.user = null;
    this.followings = [];
    this.userNotFound = false;
    this.searchedUsername = username;

    this.userService.getUser(username).subscribe({
      next: (data) => {
        this.user = data;
      },
      error: (error) => {
        this.userNotFound = true;
      },
    });

    this.userService.getFollowings(username).subscribe({
      next: (data) => {
        this.followings = data;
      },
      error: (error) => {},
    });
  }
}
