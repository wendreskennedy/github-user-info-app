import { Component, Input } from '@angular/core';
import { NgIf } from '@angular/common';

@Component({
  selector: 'app-user-not-found',
  standalone: true,
  imports: [NgIf],
  template: `
    <div
      class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-6"
      *ngIf="username"
    >
      <div class="flex items-center">
        <svg
          class="w-5 h-5 text-gray-400 mr-2"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
            clip-rule="evenodd"
          ></path>
        </svg>
        <h3 class="text-gray-800 font-medium">Usuário não encontrado</h3>
      </div>
      <p class="text-gray-700 mt-2">
        O usuário "<strong>{{ username }}</strong
        >" não foi encontrado no GitHub. Verifique se o nome de usuário está
        correto e tente novamente.
      </p>
    </div>
  `,
  styles: [],
})
export class UserNotFoundComponent {
  @Input() username: string = '';
}
