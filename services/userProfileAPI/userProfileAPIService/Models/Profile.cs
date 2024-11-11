using System.ComponentModel.DataAnnotations;
using System.Runtime.Serialization;

namespace userProfileAPIService.Models;

[DataContract]
public class Profile
{
    [Key]
    public int UserId { get; set; }
    
    [DataMember]
    public Gender? Gender { get; set; }

    [DataMember] 
    public DateTime? Birthday { get; set; }

    [DataMember]
    public string? Nationality { get; set; }
    
    [DataMember]
    public string? PhoneNumber { get; set; }
    
    [DataMember]
    public Address? Address { get; set; }

    
}