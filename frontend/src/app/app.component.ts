import { Component } from '@angular/core';
import { SearchComponent } from './components/search/search.component';
import { UserCardComponent } from './components/user-card/user-card.component';
import { CommonModule } from '@angular/common';
import { UserService } from './services/user.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, SearchComponent, UserCardComponent],
  templateUrl: './app.component.html',
})
export class AppComponent {
  title = 'github-user-info-app';
  user: any = null;

  constructor(private userService: UserService) {}

  onSearch(username: string) {
    this.userService.getUser(username).subscribe((data) => {
      this.user = data;
    });
  }
}
