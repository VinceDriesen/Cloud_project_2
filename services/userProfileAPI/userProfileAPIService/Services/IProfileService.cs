using System.ServiceModel;
namespace userProfileAPIService.Models;

[ServiceContract]
public interface IProfileService
{
    [OperationContract]
    Profile? GetProfileById(long userId);

    [OperationContract]
    List<Profile> GetAllProfiles();

    [OperationContract]
    public bool CreateProfile(int userId);

    [OperationContract]
    bool UpdateProfile(
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
        );

    [OperationContract]
    bool DeleteProfile(long userId);

    [OperationContract]
    List<Gender> GetGenders();
}