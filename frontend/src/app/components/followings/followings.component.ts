import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  standalone: true,
  selector: 'app-followings',
  imports: [CommonModule, FormsModule],
  templateUrl: './followings.component.html',
  styleUrls: ['./followings.component.css'],
})
export class FollowingsComponent {
  @Input() followings: any[] = [];

  searchText = '';

  get filteredFollowings() {
    const text = this.searchText.toLowerCase();
    return this.followings.filter(
      (f) =>
        f.login.toLowerCase().includes(text) ||
        (f.name || '').toLowerCase().includes(text)
    );
  }
}
