using SoapCore;
using Microsoft.EntityFrameworkCore;
using userProfileAPIService.Models;
using userProfileAPIService.Services;
// using SoapCore.Extensibility;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddDbContext<ProfileDbContext>(options =>
    options.UseNpgsql("Host=db_user_profile;Port=5432;Database=user_profile_database;Username=postgres;Password=postgres"));

builder.Services.AddScoped<IProfileService, ProfileService>();
// builder.Services.AddTransient<IFaultExceptionTransformer, DefaultFaultExceptionTransformer>(); // Voeg foutverwerkingsservice toe

builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var app = builder.Build();

if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseSoapEndpoint<IProfileService>("/ProfileService.asmx", new SoapEncoderOptions());

app.UseHttpsRedirection();
app.UseAuthorization();
app.MapControllers();

app.Run();