# Laravel Challenge: Routes Cleanup

This is a project where `routes/web.php` is a real mess. Individual routes, no grouping, no route names, repeating middlewares, etc.

So your task is to make it shorter and more "pretty", with whatever ways you know.

Important part is not to break the functionality, for that there are automated tests, that should still remain "green", even after your changes. 

---

## Rules: How to perform the task

I will be expecting a Pull Request to the `main` branch, containing **all** code for completely working project.

If you don't know how to contribute a PR, here's [my video with instructions](https://www.youtube.com/watch?v=vEcT6JIFji0).

**Important**: I will NOT merge the Pull Request, only comment on it, whether it's correct or not.

With my limited time, I will probably personally review random 10-20 Pull Requests, all the others will still get "karma points" for doing a good job and improving their skills.

If you have any questions, or suggestions for the future challenges, please open an Issue.

Good luck!

## How to install 

- Clone the repository with __git clone__
- Copy __.env.example__ file to __.env__ and edit database credentials there
- Run __composer install__ (if anyone got problems with composer on windows, try running it like this:  __composer install --ignore-platform-reqs__)
- Run __php artisan key:generate__
- Run __php artisan migrate --seed__ (it has some seeded data for your testing)
- That's it: launch the main URL.

```bash
php artisan key:generate
```

