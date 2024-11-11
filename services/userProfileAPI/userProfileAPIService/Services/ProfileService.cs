using System.Collections.Generic;
using System.Linq;
using Microsoft.EntityFrameworkCore;
using userProfileAPIService.Models;

namespace userProfileAPIService.Services;

public class ProfileService : IProfileService
{
    private readonly ProfileDbContext _context;

    public ProfileService(ProfileDbContext context)
    {
        _context = context;
    }

    public Profile? GetProfileById(long userId)
    {
        if (userId <= 0)
            throw new ArgumentException($"Invalid UserId. The userID is: {userId}", nameof(userId));
    
        var profile = _context.Profiles
            .Include(p => p.Address)
            .FirstOrDefault(p => p.UserId == userId);

        return profile;
    }


    public List<Profile> GetAllProfiles()
    {
        return _context.Profiles.Include(p => p.Address).ToList();
    }

    public bool CreateProfile(int userId)
    {

        if (userId <= 0) throw new ArgumentException($"Invalid UserId. The userID is: {userId}", nameof(userId));

        var profile = new Profile
        {
            UserId = userId,
            Gender = null, // Of stel deze in op een standaardwaarde
            Birthday = null, // Of stel deze in op een standaardwaarde
            Nationality = null, // Of stel deze in op een standaardwaarde
            PhoneNumber = null, // Of stel deze in op een standaardwaarde
            Address = null // Of voeg een nieuw adres object toe indien nodig
        };

        _context.Profiles.Add(profile);
        return _context.SaveChanges() > 0;
    }


    public bool UpdateProfile(
        long userId,
        string gender,
        DateTime? birthday,
        string nationality,
        string phoneNumber,
        string city,
        string country,
        string postalCode,
        string state,
        string street
        )
    {
        var existingProfile = _context.Profiles.Include(p => p.Address).FirstOrDefault(p => p.UserId == userId);
        if (existingProfile == null)
        {
            throw new ArgumentException($"Profile with UserId {userId} does not exist");
        }

        switch (gender)
        {
            case "Male":
                existingProfile.Gender = Gender.Male;
                break;
            case "Female":
                existingProfile.Gender = Gender.Female;
                break;
            case "Other":
                existingProfile.Gender = Gender.Other;
                break;
        }

        existingProfile.Birthday = birthday?.ToUniversalTime();

        existingProfile.Nationality = nationality;
        existingProfile.PhoneNumber = phoneNumber;
        
        Address address = existingProfile.Address ?? new Address();
        address.City = city;
        address.Country = country;
        address.PostalCode = postalCode;
        address.State = state;
        address.Street = street;
        existingProfile.Address = address;


        _context.Profiles.Update(existingProfile);
        return _context.SaveChanges() > 0;
    }



    public bool DeleteProfile(long userId)
    {
        var profile = _context.Profiles.Find(userId);
        if (profile == null) return false;
        _context.Profiles.Remove(profile);
        return _context.SaveChanges() > 0;
    }

    public List<Gender> GetGenders()
    {
        return Enum.GetValues(typeof(Gender)).Cast<Gender>().ToList();
    }
}