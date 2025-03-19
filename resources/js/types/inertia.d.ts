import { PageProps as InertiaPageProps } from '@inertiajs/core';

declare global {
  interface User {
    id: number;
    name: string;
    email: string;
    initials: string;
    plan?: string;
  }

  interface PageProps extends InertiaPageProps {
    auth: {
      user: User;
    };
  }
}

declare module '@inertiajs/core' {
  interface PageProps extends InertiaPageProps {
    auth: {
      user: User;
    };
  }
}

export {};
